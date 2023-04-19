<div class="bg-white p-8 rounded shadow">
    <h2>Lo que opinan nuestros clientes</h2>
    <template x-if="undefined !== $store.googleReviews">
        <div id="google-reviews" x-data="{
            reviews: [],
            show: [],
            init(){
                $watch('$store.googleReviews.reviews', (value)=>{
                    this.reviews = value;
                });
                $watch('$store.googleReviews.show', (value)=>{
                    this.show = value;
                });
            }
            }" class="relative">
            <template x-for="(review, index) in reviews">
                <div :id=" 'google-review_' + index" class="google-review animate__animated absolute"
                    x-data="{
                        init(){
                            //This is the last item
                            if(index == reviews.length -1){
                                $store.googleReviews.run();
                            }
                        }
                    }"
                    x-cloak
                    x-transition:enter="animate__fadeInRightBig"
                    x-transition:leave="animate__fadeOutLeftBig"
                    x-show="show[index]"
                    {{--style="display: none;"--}}
                    >
                    <img :src="review"/>
                </div>
            </template>
        </div>
    </template>
</div>
