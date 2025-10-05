import { store } from '../utils/store';
import GoogleReviews from '../classes/GoogleReviews';

export async function HOME_PAGE(){
    if(DEBUG) console.log("THIS IS THE HOME PAGE.");

    const googleReviews = store('googleReviews');
    await googleReviews.load();
    googleReviews.startRotation();
    if(DEBUG) console.log("REVIEWS: ",store("googleReviews").reviews);
}
