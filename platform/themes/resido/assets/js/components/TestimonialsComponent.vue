<template>
    <div class="col-lg-12 col-md-12">
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div v-if="!isLoading" v-slick class="smart-textimonials smart-center">
            <div class="item" v-for="item in data">
                <div class="item-box">
                    <div class="smart-tes-author">
                        <div class="st-author-box">
                            <div class="st-author-thumb">
                                <div class="quotes bg-blue"><i class="ti-quote-right"></i></div>
                                <img :src="item.image" class="img-fluid" :alt="item.name" />
                            </div>
                        </div>
                    </div>

                    <div class="smart-tes-content">
                        <p v-html="item.content"></p>
                    </div>

                    <div class="st-author-info">
                        <h4 class="st-author-title">{{ item.name }}</h4>
                        <span class="st-author-subtitle">{{ item.company }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: []
            };
        },
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },
        mounted() {
          this.getData();
        },
        methods: {
            getData() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        this.data = res.data.data ? res.data.data : [];
                        this.isLoading = false;
                    })
                    .catch(res => {
                        this.isLoading = false;
                        console.log(res);
                    });
            },
        },
    }
</script>
