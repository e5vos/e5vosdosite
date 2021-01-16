<template>
<div>
    <h1 style="text-align:center;font-size:32px;">Előadássávok</h1>

    <div v-show="!student.auth" class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <div class="form-group">
            <label for="studentcode">Diákkód</label>
            <input v-model="input.diakcode" class="form-control centered" type="text" id="studentcode" name="studentcode" maxlength="13" placeholder="2020A35EJG999"/>
        </div>
        <div class="form-group">
            <label for="omcode">OM-Kód utolsó 5 számjegye</label>
            <input v-model="input.omcode" class="form-control centered" type="text" id="omcode" name="omcode" maxlength="5" placeholder="93762" />
        </div>
        <div class="form-group">
            <button v-on:click="login()" class="btn btn-light">Bejelentkezés</button>
        </div>
    </div>

    <div v-if="student.auth === false" class="container alert alert-danger py-2" style="width:25%;text-align:center;">
        <h3>Hibás bejeletkezési adatok</h3>
    </div>

    <div v-if="student.auth == true" class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <h3>{{student.name}}, {{student.class}}</h3>
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

            <template v-if="student.auth===true && student.presentations[selected_slot]!=null">
                <tr>
                    <td colspan="4" style="font-weight:600;font-size:24px;">Az általad választott előadás:</td>
                </tr>
                <tr style="background-color:rgb(233, 233, 233)">
                    <td>{{student.presentations[selected_slot].presenter}}</td>
                    <td>{{student.presentations[selected_slot].title}}</td>
                    <td>{{student.presentations[selected_slot].description}}</td>
                    <td><button v-on:click="unsub" class="btn btn-secondary">{{Math.max(student.presentations[selected_slot].capacity-student.presentations[selected_slot].occupancy,0)}}</button></td>
                </tr>
                <tr><td colspan=4><button class="btn btn-danger">Jelentkezés törlése</button></td></tr>
                <tr><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px solid black;"></td></tr>
            </template>

            <tr v-for="presentation in presentations" v-bind:key="presentation.id">
                <td>{{presentation.presenter}}</td>
                <td>{{presentation.title}}</td>
                <td>{{presentation.description}}</td>
                <td><button class="btn btn-success" :disabled="!student.auth || student.presentations[selected_slot]!=null">{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
            </tr>

        </tbody>
    </table>
</div>
</template>


<script>
export default {
    data(){
        return{
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
            student:{
                auth:'',
                diakcode: '',
                omcode: '',
                presentations:[],
            },
            input: {
                diakcode:'',
                omcode:'',
            }
        }
    },
    created(){
        this.changeSlot(1)
    },
    methods: {
        fetchSlot(slot){
            fetch('/api/e5n/presentations/'+slot)
            .then(res => res.json())
            .then(res => {
                this.presentations=res.data
            })
        },

        changeSlot(slot){
            this.fetchSlot(slot)
            this.selected_slot=slot
        },

        login(dc = this.input.diakcode, oc = this.input.omcode){

            fetch('/api/e5n/student/auth/'+dc+'/'+oc),{method:'POST'}
            .then(res =>res.json())
            .then(res =>{
                this.student=res;
                this.student.omcode=oc
                this.student.auth=true
            })
        },
        unsub(){
            fetch('/api/e5n/student/unsub/'+this.student.diakcode+'/'+this.student.omcode+'/'+this.selected_slot,{method:'POST'})
            login(this.student.diakcode,this.student.omcode)
        }
    }
}
</script>
