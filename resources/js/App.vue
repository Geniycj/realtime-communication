<template>
    <v-app>
        <v-main>
            <v-container>
                <v-select label="Users select">
                </v-select>
                <v-text-field label="Put here the text">

                </v-text-field>
                <v-btn> Send event</v-btn>
                <v-row>
                    <v-col cols="12">
                        <v-card>
                            <v-card-title class="text-center">Result</v-card-title>
                            <v-card-text>
                                <div>My events list:
<!--                                    <XmlViewer v-if="format === 'xml'" :xml="result"/>-->
<!--                                    <JsonViewer v-else :value="result" copyable sort theme="jv-light"/>-->
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script lang="ts" setup>
// import Filters from "./application/Filters.vue";
// import XmlViewer from 'vue3-xml-viewer'
import {ref} from "vue";

const format = ref('xml');
const loading = ref(false);
const result = ref();
const fetchResults = async (e) => {
    loading.value = true
    format.value = e.output_format
    try {
        const response = await fetch('/api/random-user/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(e)
        });
        if (e.output_format === 'xml') {
            result.value = await response.text();
        } else {
            result.value = await response.json()
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
    loading.value = false
};
</script>
