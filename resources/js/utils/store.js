export function store(key, value){
    if(value === undefined){
        return Alpine.store(key);
    }else{
        Alpine.store(key, value);
    }
}
