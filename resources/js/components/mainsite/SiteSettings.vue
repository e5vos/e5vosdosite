<template>
<div>
    <div class="col-6" style="margin:auto">
        <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;">
            <thead class="thead-dark">
                <th>
                    <h2>kulcs</h2>
                </th>
                <th>
                    <h2>állapot</h2>
                </th>
                <th>
                    <h2>OK</h2>
                </th>
            </thead>
            <tr v-for="setting in settings" v-bind:key="setting.key">
                <td>{{setting.key}}</td>
                <td><input type="text" class="form-control" v-model="setting.value" /></td>
                <td><button class="btn btn-dark" v-on:click="changeSetting(setting)">OK</button></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" name="newKey" id="newKey" v-model="newKey" ></td>
                <td><input type="text" class="form-control" name="newValue" id="newValue" v-model="newValue"></td>
                <td><button class="btn btn-dark" v-on:click="newSetting()">Új beállítás</button></td>
            </tr>
        </table>
    </div>
</div>

</template>
<script>
export default {
    data() {
        return {
            settings: [],
            newKey: '',
            newValue:''
        }
    },
    created(){
        this.settings = this.$attrs.settings
    },
    methods: {
        async changeSetting(setting){
            const requestOptions = {
                method: "PUT",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    value: setting.value
                })
            }
            await fetch('/setting/'+setting.key,requestOptions);
        },
        async newSetting(){
            const requestOptions = {
                method: "PUSH",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    key : newKey ,
                    value: newValue
                })
            }
            await fetch('/setting/',requestOptions);
        }
    }
}
</script>
