import { createApp } from 'vue';

createApp({
    compilerOptions: {
        delimiters: ["${", "}$"]
    },
    data() {
        return {
            timeout: null,
            isLoading: false,
            questions: null,
        }
    },
    methods: {
        updateInput(event: KeyboardEvent){
            clearTimeout(this.timeout);
            this.timeout = setTimeout(async () => {
                const value = this.$refs.input.value;
                if(value?.length){
                    try {
                        this.isLoading = true;
                        const reponse = await fetch(`/question/search/${value}`)
                        const body = await reponse.json();
                        this.questions = JSON.parse(body);
                        this.isLoading = false;
                        console.log(this.questions);
                    } catch (error) {
                        this.isLoading = false;
                        this.questions = null;
                    }
                } else {
                    this.isLoading = false;
                    this.questions = null;
                }
            }, 1000);
        }
    }
}).mount('#search');