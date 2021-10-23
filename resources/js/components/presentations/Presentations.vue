<template>
<div>
    <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>

    <div class="container py-2" style="width:fit-content;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <span v-show="user==null">
            <div id="gSignIn" class="g-signin2"></div>
        </span>
        <div v-if="user && user.getBasicProfile()" class="container py-2">
            <h3>{{user.getBasicProfile().getName()}}</h3>
        </div>
        <div v-if="authFail" class="container card bg-info py-2">
            <h3 style="color:red;">Sikertelen bejelentkezés a DÖ szerverére!</h3>
            <p>A hiba leggyakoribb oka a szerverünk túlterhelődése, kérlek próbálkozz később! Ha a hiba továbbra is fennáll, kérj segítséget!</p>
        </div>
        <div v-if="signupError == 403" class="container py-2">
            <h3 style="color:red;">Nem jelentkezhetsz előadásokra</h3>
        </div>
    </div>
    <div class="container py-2" style="width:fit-content;text-align:center;">
        <button class="btn btn-warning" v-if="user && authFail && !authLock" v-on:click="authenticate(user,false)">Újrapróbálkozás</button>
        <button class="btn btn-primary" v-if="user" v-on:click="logout">Kijelentkezés</button>
    </div><!--AUTH the student -->

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

            <template v-if="user && selected_presentations && selected_presentations[selected_slot]">
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
                    <td><button class="btn btn-success" :disabled="authFail || !user || (selected_presentations!= null && selected_presentations[selected_slot]!=null) || disableSignup" v-on:click="signUp(presentation.id)">{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
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
            statusmsg: "Jelentkezz be az adatok automatikus frissítéséhez!",
            counter: 0,
            performanceDangerLevels:{
                warning: 400,
                danger: 900
            },
            authFail: false,
            authLock: false,
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
                    setTimeout(() => this.userListener(gapi.auth2.getAuthInstance.currentUser), 100)
                    this.changeSlot(1)
                    setTimeout(this.tick,this.refreshIntervalTime/10)
                    setTimeout(this.authenticate,1000)
                })
            })
    },
    mounted(){
        this.refreshBar = document.getElementById("refreshBar")
    },
    updated(){
        if(!this.user) gapi.signin2.render("gSignIn")

    },
    methods: {

        userListener(currentUser){

            if(currentUser != null && currentUser.isSignedIn()){
                this.authenticate(currentUser)
            }
        },
        tick(){

            var currentUser =  gapi.auth2.getAuthInstance() ? gapi.auth2.getAuthInstance().currentUser.get() : null;
            if(currentUser!= null && currentUser.isSignedIn()) this.user = currentUser
            if(!this.authFail && this.user!=null){
                if(this.counter > 100){
                    this.counter = 0;
                    if(this.user!=null) this.refresh();
                    this.updateRefreshBarColor();

                }
                this.refreshBar.style.width=this.counter+"%"
                this.counter+=10;
            }
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
                method: "POST",
                headers:{"Content-Type":"application/json"}
            }
            return fetch('/api/e5n/student/presentations/',requestOptions).then(res => res.status==200 ? res.json() : null).then(res=>{
                if(res!=null){
                    res.forEach(element => this.selected_presentations[element.slot]=element)
                    this.$forceUpdate();
                }

            })
        },

        retryAuthenticate(user,times){
            if(times<=0) return;
            setTimeout(()=>{
                if(this.authFail) this.authenticate(user,false)
                this.retryAuthenticate(user,times-1)
            },2000)
        },

        authenticate(user=this.user,retry=true){
            if(user==null || this.authLock){
                return
            }
            this.authLock = true;
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    id_token : user.getAuthResponse().id_token
                })
            }
            return fetch("/api/student/auth",requestOptions).then(res => {
                if(res.ok) {
                    this.authFail = false
                    this.user = user
                    this.fetchUserData();
                }else{
                    this.authFail = true
                    this.refreshBar.classList.add("bg-warning")
                    if(retry) this.retryAuthenticate(user,2)

                }
                this.authLock = false;
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

        logout(){
             gapi.auth2.getAuthInstance().signOut().then(()=>{
                this.user = null
                this.selected_presentations = []
                gapi.signin2.render("gSignIn")
             })
        },

        updateRefreshBarColor(){
            var userDataRefreshTime = performance.getEntriesByName("userDataRefresh").length == 0 ? 0 : performance.getEntriesByName("userDataRefresh")[performance.getEntriesByName("userDataRefresh").length-1].duration;
            var slotRefreshTime =  performance.getEntriesByName("slotRefresh").length == 0 ? 0 : performance.getEntriesByName("slotRefresh")[performance.getEntriesByName("slotRefresh").length-1].duration;
            var responseTime = Math.max(userDataRefreshTime,slotRefreshTime);
            if(responseTime < this.performanceDangerLevels.warning){
                this.refreshBar.classList.remove("bg-danger")
                this.refreshBar.classList.remove("bg-warning")
                this.statusmsg = ""
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
        }

    }
}
</script>
