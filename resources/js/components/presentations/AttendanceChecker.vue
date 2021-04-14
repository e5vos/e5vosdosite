<template>
    <div>
        <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="py-2 container-fluid" style="background-color:rgba(255, 255, 255, 0.5)">
                <table class="table table-light" style="text-align:center;width:100%;table-layout: auto;">
                    <thead class="thead-dark">
                        <tr><th colspan=3>{{signups[0].title}}</th></tr>
                        <tr>
                            <th scope="col">Osztály</th>
                            <th scope="col" colspan=2>Név</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="signup in signups" v-bind:key="signup.id">
                            <td>{{signup.class_id}}</td>
                            <td>{{signup.name}}</td>
                            <td><input type="checkbox" class="form-control"   v-bind:checked="signup.present" v-on:click="toggle(signup)"  ></td>
                        </tr>
                        <tr><td colspan=3><button style="width:25%" class="btn btn-dark">OK</button></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            signups: [],
            prescode: '',
            signup:{
                id:'',
                presentation_id:'',
                student_id:'',
                present:'',
            },

        }
    },
    created(){
        this.prescode = window.location.pathname,
        this.fetchSlot(this.prescode)
    },
    methods: {
        fetchSlot(prescode){
            fetch('/api'+prescode)
            .then(res => res.json())
            .then(res => {
                this.signups=res.data
            })
        },
        toggle(signup) {
            signup.present = signup.present === 1 ? 0 : 1
            fetch('/api' + this.prescode + '/' + signup.id)
        },
    }
}
</script>

