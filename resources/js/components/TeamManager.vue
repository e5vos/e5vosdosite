<template>
</template>
<script>
export default {
    data(){
        return{
            team: {
                name: '',
                code: '',
                participations: [],
                members : [],
            },
        }
    },
    created(){

    },
    methods: {
        loadTeam(teamcode){
            fetch('/api/e5n/teams/manage/'+code)
            .then(res => res.json())
            .then(res => {
                this.team=res.data
            })
        },
        fetchMembers(){
            this.team.members.forEach(member =>{
                member = fetchMember(member.code)
            })
        },
        fetchMember(studentcode){
            fetch('/api/e5n/student/'+code)
            .then(res => res.json())
            .then(res => {
                return res.data
            })
        },
        save(slot){

            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify(this.team)
            }

            this.team.members.forEach(member =>{
                if(!/[0-9]{4}[A-Z]{1}[0-9]{2}EJG[0-9]{3}/.test(member.code)){
                    console.log(member)
                    return
                }
            })


            fetch('/api/e5n/teams/',requestOptions)
            .then(res =>{
                if(!res.ok) throw new Error(res.status)
                res.json()
            })
            .then(res =>{
                console.log("Team Saved")
            }).catch(error=>{
                console.error("Team NOT Saved, ERROR: " +error)
            })
        },
    }
}
</script>
