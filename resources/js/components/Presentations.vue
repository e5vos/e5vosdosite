<template>
<div>
    <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>

    <div class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <div id="gSignIn" v-if="!user" class="g-signin2"></div>
        <div v-if="user" class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
            <h3>{{user.getBasicProfile().getName()}}</h3>
        </div>
        <div v-if="authFail" class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
            <h3 style="color:red;">Sikertelen bejelentkezés</h3>
        </div>
        <button class="btn btn-primary" v-if="user" v-on:click="logout">Kijelentkezés</button>
        <button class="btn btn-primary" v-on:click="test">Test</button>

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
                    <td><button class="btn btn-success" :disabled="!user || (selected_presentations!= null && selected_presentations[selected_slot]!=null)" v-on:click="signUp(presentation.id)">{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
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
            presentations: [],
            selected_slot: '',
            presentation:{
                id:'',
                slot:'',
                title:'',
                description:'',
                location:'',
                capacity:'',
                code:'',
                occupancy: '',
            },
            user : '',
            selected_presentations: [],
            input: {
                diakcode:'',
                omcode:'',
            }
        }
    },
    created(){
        this.changeSlot(1)
    },
    updated(){
        gapi.auth2.init()
        gapi.auth2.getAuthInstance().currentUser.listen((currentUser) => {
            if(currentUser.isSignedIn()) this.authenticate(currentUser);
        })
        if(!this.user) gapi.signin2.render("gSignIn")
    },
    methods: {
        fetchSlot(slot){
            fetch('/api/e5n/presentations/'+slot)
            .then(res => res.json())
            .then(res => {
                this.presentations=res.data
            })
        },

        fetchUserData(){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"}
            }
            fetch('/api/e5n/student/presentations/',requestOptions).then(res => res.json())
            .then(res => {
                for(var i=2; i >= 0; i--){
                    res[i+1]=res[i];
                }
                this.selected_presentations = res;
            })
        },

        changeSlot(slot){
            this.fetchSlot(slot)
            this.selected_slot=slot
        },

        authenticate(user){

            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    id_token : user.getAuthResponse().id_token
                })
            }
            fetch("/api/student/auth",requestOptions).then(res => {
                if(res.ok) {
                    this.authFail = false
                    this.user = user
                    this.fetchUserData();
                }else{
                    console.log(user);
                    this.authFail = true
                }
            })
        },

        test(){
            this.fetchUserData();
            console.log(this.selected_presentations)
        },

        signUp(presentationId){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    presentation: presentationId
                })
            }
            fetch("/api/e5n/presentations/signup/",requestOptions)
            .then(res => {
                if(res.ok){
                    this.fetchUserData();
                }else{
                    console.error("Signup error occured");
                }
            })
        },
        refresh(){
            this.fetchSlot(this.selected_slot)
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
            login(this.student.diakcode,this.student.omcode)
        },

        logout(){
             gapi.auth2.getAuthInstance().signOut().then(()=>{
                this.user = null
                this.selected_presentations = null
                gapi.signin2.render("gSignIn")
             })
        }
    }
}
</script>
