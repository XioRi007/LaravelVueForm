import { createStore, createLogger } from 'vuex'
import register from './modules/register'
import members from './modules/members'
import auth from "../store/modules/auth";

export default createStore({
  modules: {
    register,
    members,
    auth
  },
})
