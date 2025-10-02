import { store } from '../utils/store';
import GoogleReviews from '../classes/GoogleReviews';

export async function HOME_PAGE(){
    if(DEBUG) console.log("THIS IS THE HOME PAGE.");
    store("googleReviews", new GoogleReviews());
    await store("googleReviews").load();
    if(DEBUG) console.log("REVIEWS: ",store("googleReviews").reviews);
}
