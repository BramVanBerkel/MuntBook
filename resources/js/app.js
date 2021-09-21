require('./bootstrap');

require('alpinejs');

window.searchApp = () => {
    return {
        query: null,

        search() {
            console.log(this.query)
        }
    }
}
