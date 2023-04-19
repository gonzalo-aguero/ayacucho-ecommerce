class GoogleReviews {
    static api = location.origin + "/api/google-reviews";
    constructor() {
        this.reviews = [];//[{"image-source"}]
        this.show = [];//[false, false, false, true, false, false, ...]
    }
    async load() {
        const response = await fetch(GoogleReviews.api);
        if (response.ok) {
            const data = await response.json();
            this.reviews = data;
            for(let i = 0; i < this.reviews.length; i++){
                this.show[i] = false;
            }
        } else {
            throw new Error(`Failed to load data from ${this.api}`);
        }
    }
    run(){
        //const elements = document.querySelectorAll("#google-reviews > .google-review");
        //console.log(elements);
        let i = -1;
        const interval = setInterval(()=>{
            this.show[i++] = false;
            if(i == this.show.length) i = 0;
            this.show[i] = true;
        }, 2500);
    }
}
export default GoogleReviews;
