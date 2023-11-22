<template>
    <v-app>
        <v-main>
            <v-container>
                <v-select v-model="selectedUser" :item-props="itemProps" :items="results" label="Users select" variant="outlined" @click="fetchResults" :loading="loading">

                </v-select>
                <v-text-field label="Text of Event" v-model="eventText">

                </v-text-field>
                <v-btn color="primary" @click="sendEvent"> Send event</v-btn>
                <v-row>
                    <v-col cols="12">
                        <v-card :loading="loading2">
                            <v-card-title class="text-center">
                                <v-row>
                                    <v-col cols="auto">
                                        Result
                                    </v-col>
                                    <v-spacer />
                                    <v-btn icon variant="text" @click="fetchNotifications">
                                        <v-icon>mdi-refresh</v-icon>
                                    </v-btn>
                                </v-row>
                            </v-card-title>
                            <v-card-text>
                                <div>My events list:
                                </div>
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
import {ref, onMounted} from "vue";
interface ResultsType {
    page: any
}
const props = defineProps<ResultsType>();
const format = ref('json');
const loading = ref(false);
const loading2 = ref(false);
const eventText = ref('');
const results = ref([]);
const selectedUser = ref(0);
const csrf = ref();
const activeUserIds = ref([]);
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
function itemProps (item) {
    return {
        title: item.name,
        subtitle: item.email + " ("+ item.id+")",
        value: item.id,
        disabled: true,
    }
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
    fetchNotifications()
    const pusher = Pusher;
    const echo = new Echo({
        broadcaster: "pusher",
        key: "888",
        cluster: "mt1",
        forceTLS: false,
        wsHost: 'localhost',
        wsPort: 6001,
        disableStats: false,
    });
    console.log('Connecting to websocket...')
    echo.channel("private-App.Models.User."+ props.page.props.auth.user.id)
   .notification((notification) => {
       console.log(notification)
       notifications.value.push(notification)
    })
        echo.join('Online.Users')
        .here((users) => {
          // ...
          console.log('Here')
          activeUserIds.value.splice(0, activeUserIds.value.length, ...users);
        })
        .joining((userJoining) => {
          console.log('Joining')
          addUser(userJoining);
          console.log(userJoining.id);
          console.log(userJoining.name);
        })
        .leaving((userLeaving) => {
          console.log('userLeaving')
          removeUser(userLeaving.id)
          console.log(userLeaving.name);
        })
        .error((error) => {
          console.error(error);
        });;
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
    }
}
function removeUser(id){
  activeUserIds.value = activeUserIds.value.filter(item => item.id !== id);
}
function addUser(user){
  activeUserIds.value.push(user);
}
</script>
