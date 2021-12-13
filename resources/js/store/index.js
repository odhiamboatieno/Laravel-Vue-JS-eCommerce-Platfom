export default {
    state: {

        cart_items: [],
        total_cart_item: 0,
        total_cart_amount: 0,
        isLoading: false,


    },
    getters: {

        cart_items(state) {
            return state.cart_items
        },
        cart_count(state) {
            return state.total_cart_item
        },
        cart_total(state) {

            return state.total_cart_amount
        },
        cart_loading(state) {
            return state.isLoading
        },

        productWithId: (state) => (id) => {

            let ob = Object.values(state.cart_items);

            return ob.find(todo => todo.id == id)
        },
    },
    actions: {
        getCart(context) {
            context.commit('loading_status', true)
            axios.get(base_url + 'cart-items')
                .then((response) => {
                    context.commit('cartItems', response.data.cart_items)
                    context.commit('totalCartItem', response.data.cart_count)
                    context.commit('totalCartAmount', response.data.cart_total)
                    context.commit('loading_status', false);
                })
        },
    },
    mutations: {
        // cart mutation 
        cartItems(state, data) {
            return state.cart_items = data
        },
        totalCartItem(state, payload) {
            return state.total_cart_item = payload
        },
        totalCartAmount(state, payload) {
            return state.total_cart_amount = payload
        },
        loading_status(state, payload) {
            return state.isLoading = payload
        },

    }
}