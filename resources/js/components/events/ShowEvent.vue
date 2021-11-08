<template>
     <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="jumbotron text-center h-100">
                    <h1>{{event.name}}</h1> <br/>
                    <img src="/images/default.png" class="img-fluid rounded" alt="A képet nem sikerült betölteni."/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="jumbotron text-center" style="font-size: 18px">{{event.description}}</div>
                <div class="jumbotron text-center">
                        <a v-if="event.location != null" :href="'/e5n/locations/' + event.location_id"><h4>Helyszín: {{event.location.name}}</h4></a>
                    <h4 v-if="event.organiser_name">Szervező(k): {{event.organiser_name}}</h4>
                    <h4 v-else v-for="orga in event.organisers" v-bind:key="orga"></h4><br/>
                        <a v-if="event.is_presentation" href="/e5n/presentations"><button class="btn btn-success">Előadás Jelentkezés</button></a>
                        <button v-else-if="event.capacity!=null" onclick="signUpFunc" class="btn btn-success">Jelentkezés!</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="jumbotron text-center h-90 align-middle">
                    <h2>Értékelés:</h2><br/>
                    <h1>{{event.rating}}/10</h1> <br/>
                        <input type="range" class="form-range form-fluid" min="1" max="10" :value="event.userRating">
                        <button class="btn btn-primary" type="submit" :on-click="fetchRate()">Értékel</button>
                    <h3>Jelenleg futó események: </h3>
                    <div v-for="event in ongoing" v-bind:key="event.code">
                        <a :href="'/e5n/event/' + event.code" class="btn btn-success">{{event.name}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            event:{
                userRating: 10,
            },
            ongoing:[],
        }
    },
    mounted(){
        this.eventcode = this.$attrs.eventcode;
        this.fetchEvent();
        this.fetchOngoing();
        setInterval(this.fetchEvent(),5000);
    },
    methods: {
        async fetchEvent(eventcode = this.eventcode){
            const requestOptions = {
                method: "GET",
            }
            const res =  await fetch('/api/e5n/event/'+this.eventcode, requestOptions);
            this.event = await res.json();
        },
        async fetchOngoing(){
            const requestOptions = {
                method: "GET",
            }
            const res =  await fetch('/api/e5n/events/ongoing', requestOptions);
            this.ongoing = await res.json();
        },
        async fetchRate(){
            const requestOptions = {
                method: "POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                },
            }
            console.log(this.eventcode)
            await fetch('/api/e5n/event/' + this.eventcode + '/rate/' + this.event.userRating, requestOptions);
            this.fetchEvent();
        },

    }
}
</script>
