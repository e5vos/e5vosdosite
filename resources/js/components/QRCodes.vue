<template>
<div>
    <div v-for="(studentpage,pageno) in students" :key="pageno" class="studentcardgrid">
        <div v-for="student in studentpage" :key="student.id" class="studentcard">
            <h3>{{student.name}}</h3>
            <qrcode :value="student.code" :tag="'img'"></qrcode>
        </div>
    </div>

</div>
</template>


<script>
export default {
    data(){
        return{
            students: [],
            pagesize:20,
        }
    },
    created(){
        fetch('/api/e5n/students/')
            .then(res => res.json())
            .then(res => {
                while(res.data.length >0){
                    const page = res.data.splice(0,this.pagesize)
                    this.students.push(page)
                }
            })
    },
    methods: {

    }
}
</script>
