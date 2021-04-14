<template>
<div>
    <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>

    <div class="container py-2" style="width:fit-content;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <span v-show="user===null">
            <div id="gSignIn" class="g-signin2"></div>
        </span>
        <div v-if="user" class="container py-2">
            <h3>{{user.getBasicProfile().getName()}}</h3>
        </div>
        <div v-if="authFail" class="container py-2">
            <h3 style="color:red;">Sikertelen bejelentkezés</h3>
        </div>
        <div v-if="signupError == 403" class="container py-2">
            <h3 style="color:red;">Nem jelentkezhetsz előadásokra</h3>
        </div>
        <button class="btn btn-primary" v-if="user" v-on:click="logout">Kijelentkezés</button>

    </div>



    <br/>

    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
        <button v-for="i in 3" :key="i" v-on:click="changeSlot(i)" v-bind:class="{active: selected_slot== i }" type="button" class="btn btn-secondary">{{i}}. előadássáv</button>
    </div>

    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
        <thead class="thead-dark">
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
                    <td><button class="btn btn-success" :disabled="!user || (selected_presentations!= null && selected_presentations[selected_slot]!=null) || disableSignup" v-on:click="signUp(presentation.id)">{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
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
            authFail: false,
            authLock: false,
            presentations: [],
            selected_slot: '',
            selected_slot_before: '',
            disableSignup: false,
            user : null,
            selected_presentations: [],
            signupError : null,
        }
    },
    created(){
        this.changeSlot(1)
        setInterval(() => {
            if(this.user!=null) this.refresh();
        },2000);
    },
    updated(){
        gapi.auth2.init()
        gapi.auth2.getAuthInstance().currentUser.listen((currentUser) => {
            if(currentUser.isSignedIn()) setTimeout(() => {this.authenticate(currentUser)},100);
        })
        if(!this.user) gapi.signin2.render("gSignIn")
    },
    methods: {
        changeSlot(slot){
            this.selected_slot_before=slot
            fetch('/api/e5n/presentations/'+slot)
            .then(res => res.json())
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
            return fetch('/api/e5n/student/presentations/',requestOptions).then(res => res.json()).then(res=>{
                res.forEach(element => this.selected_presentations[element.slot]=element)
                this.$forceUpdate();
            })
        },

        retryAuthenticate(user,times){
            setTimeout(()=>{
                this.authenticate(user)
                this.retryAuthenticate(user,times-1)
            },1000)
        },

        authenticate(user){
            if(this.authLock){
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
                    if(!this.authFail) this.retryAuthenticate(user,2)
                    this.authFail = true

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
            this.changeSlot(this.selected_slot_before)
            this.fetchUserData()
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
        }
    }
}
</script>
