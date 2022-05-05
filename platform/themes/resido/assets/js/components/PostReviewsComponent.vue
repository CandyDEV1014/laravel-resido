<template>
    <div>
        <div class="property_block_wrap style-2" v-if="listReviews.length">
            <div class="property_block_wrap_header">
                <a
                    data-bs-toggle="collapse"
                    data-parent="#rev"
                    data-bs-target="#clEight"
                    aria-controls="clEight"
                    href="javascript:void(0);"
                    aria-expanded="true"
                >
                    <h4 class="property_block_title">
                        {{ meta.total }} {{ __('Comments') }}
                    </h4>
                </a>
            </div>

            <div id="clEight" class="panel-collapse collapse show">
                <div class="block-body">
                    <div class="author-review">
                        <div
                            class="comment-list"
                        >
                            <ul>
                                <li
                                    class="article_comments_wrap"
                                    v-for="item in listReviews"
                                    :key="item.id"
                                >
                                    <article>
                                        <div class="article_comments_thumb">
                                            <img
                                                :src="item.user_avatar"
                                                :alt="item.user_name"
                                            />
                                        </div>
                                        <div class="comment-details">
                                            <div class="comment-meta">
                                                <div class="comment-left-meta">
                                                    <h4 class="author-name">
                                                        {{ item.user_name }}
                                                    </h4>
                                                    <div class="comment-date">
                                                        {{ item.created_at }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="comment-text">
                                                <p>{{ item.comment }}</p>
                                            </div>
                                        </div>
                                    </article>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <a  
                        v-if="checkNextPage(meta)"
                        href="#"
                        @click.prevent="getListComments(meta.current_page + 1)"
                        class="reviews-checked theme-cl"
                    >
                        <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                        {{ __('See More Comments') }}
                    </a>
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
            listReviews: [],
            meta: {},
        };
    },
    props: {
        apiGetReviews: {
            type: String,
            default: () => null,
            required: true
        },
    },
    mounted() {
        this.getListReviews();
    },
    methods: {
        checkNextPage(meta) {
            return meta.total ? (meta.current_page * meta.per_page) < meta.total : false
        },
        getListReviews(page = 1) {
            this.isLoading = true;
            axios
                .get(this.apiGetReviews + "?page=" + page)
                .then(res => {
                    this.listReviews = this.listReviews.concat(
                        res.data.data ? res.data.data : []
                    );
                    this.meta = res.data.meta;
                    this.isLoading = false;
                })
                .catch(res => {
                    this.isLoading = false;
                    console.log(res);
                });
        }
    }
};
</script>
