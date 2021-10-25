<template>
<div>

    <div class="container py-2" style="width:fit-content;text-align:center;">
        <div v-if="signupError == 403" class="container py-2">
            <h3 style="color:red;">Nem jelentkezhetsz előadásokra</h3>
        </div>
    </div>
    <br/>

    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
        <button v-for="i in 3" :key="i" v-on:click="changeSlot(i)" v-bind:class="{active: selected_slot== i }" type="button" class="btn btn-secondary">{{i}}. előadássáv</button>
    </div>

    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
        <thead class="thead-dark">
            <tr>
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
                <th scope="col">Előadás rövid leírása</th>
                <th scope="col">Szabad</th>
            </tr>
        </thead>
        <tbody>

            <template v-if="selected_presentations && selected_presentations[selected_slot]">
                <tr>
                    <td colspan="4" style="font-weight:600;font-size:24px;">Az általad választott előadás:</td>
                </tr>
                <tr style="background-color:rgb(233, 233, 233)">
                    <td>{{selected_presentations[selected_slot].presenter}}</td>
                    <td>{{selected_presentations[selected_slot].title}}</td>
                    <td>{{selected_presentations[selected_slot].description}}</td>
                    <td><button class="btn btn-secondary">{{Math.max(selected_presentations[selected_slot].capacity-selected_presentations[selected_slot].occupancy,0)}}</button></td>
                </tr>
                <tr><td colspan=4><button v-on:click="deleteSignUp(selected_presentations[selected_slot].id)" class="btn btn-danger">Jelentkezés törlése</button></td></tr>
                <tr><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px solid black;"></td></tr>
            </template>

            <tr v-for="presentation in presentations" v-bind:key="presentation.id">
                <template v-if="!selected_presentations || !selected_presentations[selected_slot] || presentation.id!=selected_presentations[selected_slot].id">
                    <td>{{presentation.presenter}}</td>
                    <td>{{presentation.title}}</td>
                    <td>{{presentation.description}}</td>
                    <td><button class="btn btn-success" :disabled="(selected_presentations!= null && selected_presentations[selected_slot]!=null) || disableSignup" v-on:click="signUp(presentation.id)">{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
                </template>
            </tr>

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
                warning: 400,
                danger: 900
            },
            presentations: [],
            selected_slot: '',
            selected_slot_before: '',
            disableSignup: false,
            user: null,
            selected_presentations: [],
            signupError : null,
        }
    },
    created(){
            gapi.load("auth2",()=>{
                gapi.auth2.init().then(()=>{
                    gapi.auth2.getAuthInstance().currentUser.listen(this.userListener)
                    setTimeout(() => {this.user = gapi.auth2.getAuthInstance().currentUser.get()} , 100)
                    this.changeSlot(1)
                    setTimeout(this.tick,this.refreshIntervalTime/10)
                })
            })
    },
    mounted(){
        this.refreshBar = document.getElementById("refreshBar")
    },
    methods: {

        tick(){
            if(this.counter > 100){
                this.counter = 0;
                this.refresh();
                this.updateRefreshBarColor();
            }
            this.refreshBar.style.width=this.counter+"%"
            this.counter+=10;
            setTimeout(this.tick,this.refreshIntervalTime/10)
        },

        changeSlot(slot){
            this.selected_slot_before=slot
            return fetch('/api/e5n/presentations/'+slot)
            .then(res => res.status == 200 ? res.json() : null)
            .then(res => {
                this.presentations=res.data
                this.selected_slot = slot
            })
        },

        fetchUserData(){
            const requestOptions = {
                method: "POST"
            }
            return fetch('/api/e5n/student/presentations/',requestOptions).then(res => res.status==200 ? res.json() : null).then(res=>{
                if(res!=null){
                    res.forEach(element => this.selected_presentations[element.slot]=element)
                    this.$forceUpdate();
                }

            })
        },

        signUp(presentationId){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    presentation: presentationId
                })
            }
            this.disableSignup = true;
            fetch("/api/e5n/presentations/signup/",requestOptions)
            .then(res => {
                if(res.ok){
                    this.fetchUserData().then(() => {this.disableSignup = false});
                }else{
                    this.signupError = res.status;
                    this.disableSignup = false;
                }

            })
        },
        refresh(){
            performance.mark("slotRefreshMark")

            this.changeSlot(this.selected_slot_before).then(()=>{
                performance.measure("slotRefresh","slotRefreshMark");
                performance.mark("userDataRefreshMark")
                this.fetchUserData().then(()=>{
                    performance.measure("userDataRefresh","userDataRefreshMark")
                })
            })

        },
        deleteSignUp(presentation){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    presentation: presentation,
                })
            }

            fetch('/api/e5n/presentations/signup/delete/',requestOptions).then(res => {
                if(res.ok){
                    this.selected_presentations[this.selected_slot] = null
                    this.$forceUpdate()
                }
            })
        },
        updateRefreshBarColor(){
            var userDataRefreshTime = performance.getEntriesByName("userDataRefresh").length == 0 ? 0 : performance.getEntriesByName("userDataRefresh")[performance.getEntriesByName("userDataRefresh").length-1].duration;
            var slotRefreshTime =  performance.getEntriesByName("slotRefresh").length == 0 ? 0 : performance.getEntriesByName("slotRefresh")[performance.getEntriesByName("slotRefresh").length-1].duration;
            var responseTime = Math.max(userDataRefreshTime,slotRefreshTime);
            if(responseTime < this.performanceDangerLevels.warning){
                this.refreshBar.classList.remove("bg-danger")
                this.refreshBar.classList.remove("bg-warning")
                this.statusmsg = "Az adatok automatikusan frissülnek!"
                if(this.refreshIntervalTime>this.refreshIntervalTimeMin) this.refreshIntervalTime-=this.refreshIntervalTimeIncrement
            }
            else if(responseTime < this.performanceDangerLevels.danger){
                this.refreshBar.classList.add("bg-warning")
                this.refreshBar.classList.remove("bg-danger")
                this.statusmsg = "Közepes válaszidő"
            }else{
                this.refreshBar.classList.add("bg-danger")
                this.statusmsg = "Extrém válaszidő"
                if(this.refreshIntervalTime<this.refreshIntervalTimeMax) this.refreshIntervalTime+= this.refreshIntervalTimeIncrement;

            }
        },
    }
}
</script>
