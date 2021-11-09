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
                    <a v-if="event.location != null" v-bind:href='"/e5n/locations/" + event.location.id'><h4>Helyszín: {{event.location.name}}</h4></a>
                    <h4 v-if="event.organiser_name">Szervező(k): {{event.organiser_name}}</h4>
                    <h4 v-else v-for="orga in event.organisers" v-bind:key="orga">{{orga}}</h4><br/>
                </div>
            </div>
            <div class="col-md-3">
                <div class="jumbotron text-center h-90 align-middle">
                    <h2>Értékelés:</h2><br/>
                    <h1>{{event.rating}}/10</h1> <br/>
                    <label v-if="user" for="ratingRange" class="form-label">{{userRating}}/10</label>
                    <label v-else for="ratingRange" class="form-label">Az értékeléshez be kell jelentkezned!</label>
                    <input type="range" id="ratingRange" name="ratingRange" class="form-range form-fluid" min="1" max="10" @change="rate" v-model="userRating" :disabled="!user">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            user: false,
            userRating : 5,
            event: {
                rating : 5,
            }
        }
    },
    created(){
        this.event = this.$attrs.event
        this.user = this.$attrs.user
        this.userRating = this.$attrs.user-rating

    },
    mounted(){
        setInterval(this.fetchEvent,10000);
    },
    methods: {
        async fetchEvent(){
            const requestOptions = {
                method: "GET",
            }
            const res =  await fetch('/api/e5n/event/'+this.event.code, requestOptions);
            this.event = await res.json();
            this.event = this.event.data
            this.$forceUpdate()
        },
        async rate(){
            if(!this.user) return;
            const requestOptions = {
                method: "PUT",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                },
                body: JSON.stringify({
                    rating : this.userRating
                })
            }
            await fetch('/api/e5n/event/'+this.event.code+"/rate",requestOptions);
        }

    }
}
</script>
