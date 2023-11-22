<template>
    <v-app>
        <v-main>
            <v-container>
              <v-row>
              <v-col cols="12">
                <v-select hide-details v-model="selectedUser" :item-props="itemProps" :items="results" label="Users select" variant="outlined" @click="fetchResults" :loading="loading" />
              </v-col>

              <v-col cols="12">
                <v-text-field single-line  variant="outlined" hide-details label="Text of Event" v-model="eventText" />
              </v-col>
              </v-row>
              <v-row>
                <v-col cols="auto">
                  <v-btn :disabled="!buttonIsActive" color="primary" @click="sendEvent"> Send event</v-btn>
                </v-col>
              </v-row>
                  <v-row>
                    <v-col cols="12">
                        <v-card :loading="loading2">
                            <v-card-title class="text-center">
                                <v-row>
                                    <v-col cols="auto">
                                      My events list:
                                    </v-col>
                                    <v-spacer />
                                    <v-btn icon variant="text" @click="fetchNotifications">
                                        <v-icon>mdi-refresh</v-icon>
                                    </v-btn>
                                </v-row>
                            </v-card-title>
                            <v-card-text>
                                <v-data-table :headers="headers" :items="notifications">
                                    <template #item.actions="{item}">
                                        <v-btn icon variant="text" @click="deleteEvent(item.id)">
                                            <v-icon>
                                                mdi-delete
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                </v-data-table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script lang="ts" setup>
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import {ref, onMounted, onBeforeUnmount, computed} from "vue";
import { useToast } from "vue-toastification";
interface ResultsType {
    page: any
}
const toast = useToast();
const props = defineProps<ResultsType>();
const format = ref('json');
const loading = ref(false);
const loading2 = ref(false);
const eventText = ref('');
const results = ref([]);
const selectedUser = ref(0);
const csrf = ref();
const activeUserIds = ref([]);
const echo = ref();
const buttonIsActive = computed(() => selectedUser.value > 0 && eventText.value !== '')
const fetchResults = async (e) => {
    loading.value = true
    format.value = e.output_format
    try {
        const response = await fetch('/api/users', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const responseResult = await response.json()
        results.value.splice(0, results.value.length, ...responseResult.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
    loading.value = false
};
const notifications = ref([]);
const headers = [
    {
        title: "Id",
        key: "id",
        align: 'center',
        value: 'id'
    },
    {
        title: "Type",
        key: "type",
        align: 'center',
        value: 'type'
    },
    {
        title: "Data",
        key: "data",
        align: 'center',
        value: 'data'
    },
    {
        title: "Actions",
        key: "actions",
        align: 'center',
        value: 'actions',
        sortable: false,
    }
];
function itemProps(item) {
  const isUserActive = activeUserIds.value.includes(item.id);

  return {
    title: item.name,
    subtitle: item.email + " (" + item.id + ")",
    value: item.id,
    disabled: !isUserActive,
  };
}
const fetchNotifications = async (e) => {
    loading2.value = true
    try {
        const response = await fetch('/api/notifications/' + props.page.props.auth.user.id, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const responseResult = await response.json()
        notifications.value.splice(0, notifications.value.length, ...responseResult);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
    loading2.value = false
};
onMounted(()=>{
    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log(csrf.value)
    if(!csrf.value){
        window.location.reload();
    }
    fetchNotifications();
    const pusher = Pusher;
    echo.value = new Echo({
        broadcaster: "pusher",
        key: "888",
        cluster: "mt1",
        forceTLS: false,
        wsHost: 'localhost',
        wsPort: 6001,
        disableStats: false,
    });
    console.log('Connecting to websocket...')
    echo.value.channel("private-App.Models.User."+ props.page.props.auth.user.id)
   .notification((notification) => {
       console.log(notification)
        toast.info("New message: " + notification.data)
       notifications.value.push(notification)
    })
        echo.value.join('Online.Users')
        .here((users) => {
          // ...
          console.log('Here')
          let ids = users.map((item)=>item.id);
          activeUserIds.value.splice(0, activeUserIds.value.length, ...ids);
        })
        .joining((userJoining) => {
          console.log('Joining')
          addUser(userJoining);
          toast.success('User '+ userJoining.name +" joined platform!")
          console.log(userJoining.id);
          console.log(userJoining.name);
        })
        .leaving((userLeaving) => {
          console.log('userLeaving')
          removeUser(userLeaving)
          toast.info('User '+ userLeaving.name +" left platform!")
          console.log(userLeaving.name);
        })
        .error((error) => {
          console.error(error);
        });
})
onBeforeUnmount(()=>{
  echo.value.leave('Online.Users')
})
const deleteEvent = async (id) => {
    try {
        const response = await fetch('/api/notifications/delete/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                _token: csrf.value
            })
        });
        fetchNotifications();
    } catch (error) {
        console.error('Error deleting item:', error);
    }
}
const sendEvent = async () => {
    try {
        const response = await fetch('/api/notifications/'+selectedUser.value, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                data: eventText.value,
                _token: csrf.value
            })
        });
    } catch (error) {
        console.error('Error sending event:', error);
        toast.error('Failed to send event')
    }
}
function removeUser(user){
  activeUserIds.value = activeUserIds.value.filter(item => item !== user.id);
}
function addUser(user){
  activeUserIds.value.push(user.id);
}
</script>
