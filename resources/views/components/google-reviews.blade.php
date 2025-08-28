<div class="p-8 flex flex-col items-center w-full md:w-5/6 h-80 rounded-md">
    <h2 class="text-center mt-8 mb-16 uppercase text-xl text-white font-semibold drop-shadow-2xl">Lo que opinan nuestros clientes</h2>

    <template x-if="undefined !== $store.googleReviews">
        <div id="google-reviews" x-data="{
            reviews: [],
            show: [],
            init(){
                $watch('$store.googleReviews.getReviews()', (value)=>{
                    console.log(value);
                    this.reviews = value;
                });
                $watch('$store.googleReviews.getShowStates()', (value)=>{
                    this.show = value;
                });

                // Inicializar cuando se carguen los reviews
                $watch('$store.googleReviews.isLoaded()', (loaded)=>{
                    if(loaded) {
                        this.reviews = $store.googleReviews.getReviews();
                        this.show = $store.googleReviews.getShowStates();
                    }
                });
            }
        }" class="relative w-full h-96 flex justify-center items-center p-0 md:p-2">

            <template x-for="(review, index) in reviews">
                <div :id=" 'google-review_' + index " class="google-review animate__animated
                        absolute bg-white py-6 px-0 md:p-6 rounded-md shadow-2xl
                        flex flex-col items-center justify-center
                    "
                    x-data="{
                        init(){
                            // Inicializar rotación cuando se cargue el último review
                            if(index == reviews.length -1){
                                $store.googleReviews.startRotation();
                            }
                        }
                    }"
                    x-cloak
                    x-transition:enter="animate__fadeInLeftBig"
                    x-transition:leave="animate__fadeOutRightBig"
                    x-show="show[index]"
                    >
                    <img :src="review" class="h-auto w-full md:h-44 md:w-auto select-none" draggable="false"/>
                </div>
            </template>
        </div>
    </template>
</div>
