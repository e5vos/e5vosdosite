<template>
    <table class="table table-light" style="text-align:center;width:100%;table-layout: auto;">
        <thead class="thead-dark">
            <tr><th scope="row" colspan=2>{{presentation.name}}</th></tr>
        </thead>
        <tbody>
            <tr v-for="signup in presentation.signups" v-bind:key="signup.id">
                <td>{{signup.user.name}}</td>
                <td><input type="checkbox" class="form-control" v-model="signup.present" v-on:click="toggle(signup)" ></td>
            </tr>

            <tr><td colspan=3><button style="width:25%" class="btn btn-dark">OK</button></td></tr>
        </tbody>
    </table>
</template>
<script>
export default {
    data(){
        return{
            presentation : this.$attrs.presentation,
        }
    },
    created(){
    },
    methods: {
        async toggle(signup) {
            const requestOptions = {
                method: "PUT",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                },
                body: JSON.stringify({
                    signup : signup.id,
                    present : !signup.present
                })
            }

            await fetch('/e5n/admin/presentation/' + this.presentation.code + '/attendance',requestOptions)
        },
    }
}
</script>

