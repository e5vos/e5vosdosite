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
            </thead>
            <tr v-for="setting in settings" v-bind:key="setting.key">
                <td>{{setting.key}}</td>
                <td><input type="text" class="form-control" v-model="setting.value" /></td>
                <td><button v-on:click="changeSetting(setting.key)">OK</button></td>
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
        }
    },
    created(){
        this.fetchSettings()
        setInterval(this.fetchSettings(),1000)
    },
    methods: {
        fetchSettings(){
            fetch('/api/settings')
            .then(res => res.json())
            .then(res => {
                this.settings=res
            })
        },
        settingByKey(key){
            for(var setting of this.settings){
                if(setting.key==key) return setting;
            }
            return null;
        },
        changeSetting(key){
            var csetting = this.settingByKey(key)
            if(csetting == null) return
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    setting: key,
                    newSetting: csetting.value
                })
            }
            fetch('/api/setting',requestOptions)
            .then(res => res.json())
            .then(res => {
                this.settings=res.data
            })
        }
    }
}
</script>
