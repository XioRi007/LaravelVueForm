import { createStore, createLogger } from 'vuex'
import members from './modules/members'
import auth from "../store/modules/auth";

export default createStore({
  modules: {
    members,
    auth
  },
})
