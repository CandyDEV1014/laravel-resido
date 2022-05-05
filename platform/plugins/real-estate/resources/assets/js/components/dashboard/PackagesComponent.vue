<template>
    <div>
        <div class="alert alert-info current-package">
            <p>{{ __('your_credits')}}: <strong>{{ account.credits }} {{ __('credits')}}</strong></p>
        </div>
        <div class="packages-listing">
            <div class="row">
                <div style="margin: auto; width:30px;" v-if="isLoading">
                    <half-circle-spinner
                        :animation-duration="1000"
                        :size="15"
                        color="#808080"
                    />
                </div>
                <div :class="data.length === 2 ? 'col-xs-12 col-lg-6' : 'col-xs-12 col-md-6 col-lg-4'" v-for="item in data" :key="item.id" v-if="!isLoading && data.length" style="margin-top: 30px">
                    <div class="single-pricing">
                        <span class="pricing-title">{{ item.name }}</span>
                        <h4 class="pricing-value" :class="{'line-through': item.is_promotion && Date.now() < new Date(item.promotion_time)}">{{ item.price_text }}</h4>
                        <h4 class="pricing-promotion-value" v-if="item.is_promotion && Date.now() < new Date(item.promotion_time)">{{ item.promotion_price_text }}</h4>
                        <CountdownComponent v-if="item.is_promotion && Date.now() < new Date(item.promotion_time)" :until="item.promotion_time" text="Promotion will end"></CountdownComponent>
                        <p class="pricing-duration">Period: <span>{{ item.number_of_days == -1 ? "Unlimited" : item.number_of_days }} days</span></p>
                        <ul>
                            <li>Credits: {{ item.credits }}</li>
                            <li class="">{{ item.number_of_properties == -1 ? 'Unlimited' : item.number_of_properties }} Property Submission</li>
                            <li class="">{{ item.number_of_aminities == -1 ? 'Unlimited' : item.number_of_aminities }} Aminity</li>
                            <li class="">{{ item.number_of_nearestplace == -1 ? 'Unlimited' : item.number_of_nearestplace }} Nearest Place</li>
                            <li class="">{{ item.number_of_photo == -1 ? 'Unlimited' : item.number_of_photo }} Photo</li>
                            <li :class="{'add_before': !item.is_allow_featured}">Featured Property</li>
                            <li :class="{'add_before': !item.is_allow_featured}">{{ !item.is_allow_featured ? 0 : item.is_allow_featured && item.number_of_featured == -1 ? 'Unlimited' : item.number_of_featured }} Featured Property</li>
                            <li :class="{'add_before': !item.is_allow_top}">Top Property</li>
                            <li :class="{'add_before': !item.is_allow_top}">{{ !item.is_allow_top ? 0 : item.is_allow_top && item.number_of_top == -1 ? 'Unlimited' : item.number_of_top }} Top Property</li>
                            <li :class="{'add_before': !item.is_allow_urgent}">Urgent Property</li>
                            <li :class="{'add_before': !item.is_allow_urgent}">{{ !item.is_allow_urgent ? 0 : item.is_allow_urgent && item.number_of_urgent == -1 ? 'Unlimited' : item.number_of_urgent }} Urgent Property</li>
                            <li :class="{'add_before': !item.is_auto_renew}">Auto Renew</li>
                            <li :class="{'add_before': !item.is_agent}">Agent</li>
                            <template v-for="(feature, index) in JSON.parse(item.features)">
                                <template v-if="feature[0].value">
                                    <li :key="index">{{ feature[0].value}}</li>
                                </template>
                            </template>
                        </ul>   
                        <a class="pricing-btn" @click="postSubscribe(item.id)" :disabled="isSubscribing">Activate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HalfCircleSpinner} from 'epic-spinners'
    import CountdownComponent from '../CountdownComponent.vue';

    export default {
        components: {
            HalfCircleSpinner,
            CountdownComponent
        },

        data: function() {
            return {
                isLoading: true,
                isSubscribing: false,
                data: [],
                account: {},
                currentPackageId: null,
            };
        },

        mounted() {
            this.getData();
        },

        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
            subscribe_url: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getData() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                        } else {
                            this.data = res.data.data.packages;
                            this.account = res.data.data.account;
                        }
                        this.isLoading = false;
                    });
            },

            postSubscribe(id) {
                this.isSubscribing = true;
                this.currentPackageId = id;
                axios.put(this.subscribe_url, {id: id})
                    .then(res => {
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                        } else {
                            if (res.data.data && res.data.data.next_page) {
                                window.location.href = res.data.data.next_page;
                            } else {
                                this.account = res.data.data.account;
                                Botble.showSuccess(res.data.message);
                                setTimeout(() => {
                                    window.location.href = res.data.data.dashboard_page;
                                }, 1000);
                                // this.getData();
                            }
                        }
                        this.isSubscribing = false;
                    });
            }
        }
    }
</script>
