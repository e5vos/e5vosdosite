<template>
<div>
    <h1 style="text-align:center;font-size:32px;">Programok</h1>

    <div v-if="student.auth === false" class="container alert alert-danger py-2" style="width:25%;text-align:center;">
        <h3>Hibás bejeletkezési adatok</h3>
    </div>

    <div v-if="student.auth == true" class="container py-2" style="width:25%;text-align:center;background-color:rgba(133, 133, 133, 0.397)">
        <h3>{{student.name}}, {{student.class}}</h3>
    </div>

    <br/>

    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
        <button v-for="i in 3" :key="i" v-on:click="changeWeight(i)" v-bind:class="{active: selected_weight== i }" type="button" class="btn btn-secondary">{{['kis', 'közepes', 'nagy'][i-1]}} program</button>
    </div>

    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Program neve</th>
                <th scope="col">Program kezdete</th>
                <th scope="col">Program vége</th>
                <th scope="col">Helyszín</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="event in events" v-bind:key="event.id">
            <td><a v-bind:href="'/e5n/events/'+event.start+'/'+event.name">{{event.name}}</a></td>
                <td>{{event.start}}</td>
                <td>{{event.end}}</td>
                <td>{{event.location_id}}</td>
            </tr>

        </tbody>
    </table>
</div>
</template>


<script>
export default {
    data(){
        return{
            events: [],
            event:{
                id:'',
                name:'',
                description:'',
                start:'',
                end:'',
                weight:'',
                image_id: '',
            },
            student:{
                auth:'',
                diakcode: '',
                omcode: '',
                events: [],
            },
            input: {
                diakcode:'',
                omcode:'',
            },
            ongoing: [],
            selected_weight: 1,
        }
    },
    created(){
        this.changeWeight(this.weight)
        this.fetchOngoing()
    },
    methods: {
        fetchEvents(weight){
            fetch('/api/e5n/events/'+weight)
            .then( res => res.json())
            .then( res => this.events = res.data )
        },
        fetchOngoing(){
            fetch('/api/e5n/events/ongoing')
        },

        changeWeight(weight=this.selected_weight){
            this.fetchEvents(weight)
            this.selected_weight=weight
        }
    }
}
</script>
