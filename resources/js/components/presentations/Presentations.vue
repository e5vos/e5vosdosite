<template>
<div>

    <div class="container py-2" style="width:fit-content;text-align:center;">
        <div v-if="signupStatus == 403" class="alert alert-danger py-2">
             <h3 class="alert-text">Nem jelentkezhetsz előadásokra</h3>
        </div>
        <div v-if="signupStatus == 400" class="alert alert-danger py-2">
            <h3 class="alert-text">Az előadás betelt</h3>
        </div>
    </div>
    <br/>

    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
        <button v-for="i in 3" :key="i" v-on:click="changeSlot(i)" v-bind:class="{active: selected_slot== i }" type="button" class="btn btn-secondary">{{i}}. előadássáv</button>
    </div>

    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
        <thead class="thead-dark">
            <tr v-if="isUser">
                <th colspan="4">
                    <span>{{statusmsg}}</span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="transition: width 0.6s ease;" role="progressbar" id="refreshBar"></div>
                    </div>
                </th>
            </tr>
            <tr>
                <th scope="col">Előadó</th>
                <th scope="col">Előadás címe</th>
                <th scope="col">Helyek</th>
            </tr>
        </thead>

            <tbody v-if="selected_presentations && selected_presentations[selected_slot]">
                <tr>
                    <td colspan="4" style="font-weight:600;font-size:24px;">Az általad választott előadás:</td>
                </tr>
                <tr style="background-color:rgb(233, 233, 233)">
                    <td>{{selected_presentations[selected_slot].organiser_name}}</td>
                    <td>{{selected_presentations[selected_slot].name}}</td>
                    <td><button class="btn btn-secondary">{{selected_presentations[selected_slot].capacity ? selected_presentations[selected_slot].capacity-selected_presentations[selected_slot].occupancy : "Korlátlan"}}</button></td>
                </tr>
                <tr><td colspan=4><button v-on:click="deleteSignUp(selected_presentations[selected_slot].code)" class="btn btn-danger">Jelentkezés törlése</button></td></tr>
                <tr><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px solid black;"></td></tr>
            </tbody>

            <tbody v-for="(presentation,index) in presentations" v-bind:key="presentation.id">
                <template v-if="!selected_presentations || !selected_presentations[selected_slot] || presentation.id!=selected_presentations[selected_slot].id">
                    <tr>
                        <th scope="row">{{presentation.organiser_name}}</th>
                        <td>{{presentation.name}}</td>
                        <td><button class="btn btn-success" :disabled="(selected_presentations!= null && selected_presentations[selected_slot]!=null) || disableSignup" v-on:click="signUp(presentation.code)">{{presentation.capacity ? presentation.capacity-presentation.occupancy : "Korlátlan"}}</button></td>
                    </tr>
                    <tr>
                        <td colspan="3">{{presentation.description}}</td>
                    </tr>
                    <tr v-if="index != presentations.length-1 "><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px dashed lightgrey;"></td></tr>
                </template>
            </tbody>

    </table>
</div>
</template>


<script>
export default {
    data(){
        return{
            api : null,
            refreshIntervalTimeMin: 5000,
            refreshIntervalTime: 5000,
            refreshIntervalTimeIncrement: 1000,
            refreshIntervalTimeMax: 10000,
            statusmsg: "Az adatok automatikusan frissülnek!",
            counter: 0,
            performanceDangerLevels:{
                warning: 700,
                danger: 1500
            },
            presentations: [],
            selected_slot: '',
            selected_slot_before: '',
            disableSignup: false,
            isUser: false,
            selected_presentations: [],
            signupStatus : null,
        }
    },
    created(){
            this.changeSlot(1)
            this.fetchUserData()
            setTimeout(this.tickSlot,this.refreshIntervalTime/10)

    },
    methods: {
        async tickSlot(){
            if(this.counter >= 100){
                await this.refreshSlot();
            }
            if(this.isUser){
                this.counter+=10;
                document.getElementById("refreshBar").style.width=this.counter+"%"
            }
            setTimeout(this.tickSlot,this.refreshIntervalTime/10)
        },

        changeSlot(slot){
            this.selected_slot_before=slot
            return fetch('/api/e5n/presentations/'+slot)
            .then(res => res.status == 200 ? res.json() : null)
            .then(res => {
                this.presentations=res
                this.selected_slot = slot
            })
        },

        async fetchUserData(){
            const requestOptions = {
                method: "GET",
                "X-CSRF-TOKEN": window.Laravel.csrfToken,
            }
            const res = await fetch('/api/e5n/student/presentations/',requestOptions)

            this.isUser = res.status==200

            await res.json().then(data=>{
                if(data!=null) data.forEach(element => this.selected_presentations[element.slot]=element)
            }).catch(() => {
                this.selected_presentations = []
            })

        },

        async signUp(presentationCodede){
            const requestOptions = {
                method: "POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                }
            }
            const res = await fetch("/e5n/event/"+presentationCode+"/signup/",requestOptions)

            if(res.status==200){
                await this.fetchUserData();
            }

            this.signupStatus = res.status;
            this.$forceUpdate()
            this.disableSignup = false
        },
        async refreshSlot(){
            this.counter = 0;
            performance.mark("slotRefreshMark")

            await this.changeSlot(this.selected_slot_before)

            performance.measure("slotRefresh","slotRefreshMark")
            this.updateRefreshBarColor();

        },
        async deleteSignUp(presentationCode){
            const requestOptions = {
                method: "DELETE",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                }
            }
            constis.fetchUserData();
                this.$forceUpdate();
        },

        updateRefreshBarColor(){
            var responseTime =  performance.getEntriesByName("slotRefresh").length == 0 ? 0 : performance.getEntriesByName("slotRefresh")[performance.getEntriesByName("slotRefresh").length-1].duration;
            var refreshBar = document.getElementById("refreshBar")
            if(responseTime < this.performanceDangerLevels.warning){
                refreshBar.classList.remove("bg-danger")
                refreshBar.classList.remove("bg-warning")
                this.statusmsg = "Az adatok automatikusan frissülnek!"
                if(this.refreshIntervalTime>this.refreshIntervalTimeMin) this.refreshIntervalTime-=this.refreshIntervalTimeIncrement
            }
            else if(responseTime < this.performanceDangerLevels.danger){
                refreshBar.classList.add("bg-warning")
                refreshBar.classList.remove("bg-danger")
                this.statusmsg = "Közepes válaszidő"
            }else{
                refreshBar.classList.add("bg-danger")
                this.statusmsg = "Extrém válaszidő"
                if(this.refreshIntervalTime<this.refreshIntervalTimeMax) this.refreshIntervalTime+= this.refreshIntervalTimeIncrement;

            }
        }
    }
}
</script>
