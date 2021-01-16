
<template>
<div>
    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
            <button v-for="i in 3" :key="i" v-on:click="changeSlot(i)" v-bind:class="{active: selected_slot== i }" type="button" class="btn btn-secondary">{{i}}. előadássáv</button>
    </div>

    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;">
        <thead class="thead-dark">
            <tr><th colspan="3">Nem jelentkezett</th></tr>
            <tr>
                <th>Osztály</th>
                <th>Diákkód</th>
                <th>Név</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="student in students" :key="student.code">
                <td>{{student.class_name}}</td>
                <td><button class="btn btn-danger" v-on:click="magantanulo(student.code)">{{student.code}}</button></td>
                <td>{{student.name}}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>
<script>
export default {
    data(){
        return{
            students:[]
        }
    },
    created(){
        this.changeSlot(1)
    },
    methods: {

        fetchSlot(slot){
            fetch('/api/e5n/students/nemjelentkezett/'+slot)
            .then(res => res.json())
            .then(res => {
                this.students=res
            })
        },

        changeSlot(slot){
            this.fetchSlot(slot)
            this.selected_slot=slot
        },
        magantanulo(){
            this.update()
        }
    }
}
</script>
