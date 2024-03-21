import { createRouter, createWebHistory } from 'vue-router'
import dashboard from '../views/DashboardView.vue'
import Home from '../views/Home.vue'

const guest = (to, from, next) => {
  if (!localStorage.getItem("authToken")) {
    return next();
  } else {
    return next("/");
  }
};
const auth = (to, from, next) => {
  if (localStorage.getItem("authToken")) {
    return next();
  } else {
    return next("/login");
  }
};

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: dashboard
    },
    {
      path: "/login",
      name: "Login",
      beforeEnter: guest,
      component: () =>
        import(/* webpackChunkName: "login" */ "../views/Login.vue")
    },
    {
      path: "/register",
      name: "Register",
      beforeEnter: guest,
      component: () =>
        import(/* webpackChunkName: "register" */ "../views/Register.vue")
    },
    {
      path: "/verify/:hash",
      name: "Verify",
      beforeEnter: auth,
      props: true,
      component: () =>
        import(/* webpackChunkName: "verify" */ "../views/Verify.vue")
    },
    {
      path: '/services',
      name: 'ServiceIndex',
      component: () => import('../views/Service/ServiceIndex.vue')
    },
    {
      path: '/services/create',
      name: 'ServiceCreate',
      component: () => import('../views/Service/ServiceCreate.vue')
    },
    {
      path: '/services/:id/edit',
      name: 'ServiceEdit',
      component: () => import('../views/Service/ServiceEdit.vue'),
      props:true
    },
    {
      path: '/bed_types',
      name: 'BedTypeIndex',
      component: () => import('../views/BedType/BedTypeIndex.vue')
    },
    {
      path: '/bed_types/create',
      name: 'BedTypeCreate',
      component: () => import('../views/BedType/BedTypeCreate.vue')
    },
    {
      path: '/bed_types/:id/edit',
      name: 'BedTypeEdit',
      component: () => import('../views/BedType/BedTypeEdit.vue'),
      props:true
    },
    {
      path: '/room_types',
      name: 'RoomTypeIndex',
      component: () => import('../views/RoomType/RoomTypeIndex.vue')
    },
    {
      path: '/room_types/create',
      name: 'RoomTypeCreate',
      component: () => import('../views/RoomType/RoomTypeCreate.vue')
    },
    {
      path: '/room_types/:id/edit',
      name: 'RoomTypeEdit',
      component: () => import('../views/RoomType/RoomTypeEdit.vue'),
      props:true
    },
    {
      path: '/rooms',
      name: 'RoomIndex',
      component: () => import('../views/Room/RoomIndex.vue')
    },
    {
      path: '/rooms/create',
      name: 'RoomCreate',
      component: () => import('../views/Room/RoomCreate.vue')
    },
    {
      path: '/rooms/:id/edit',
      name: 'RoomEdit',
      component: () => import('../views/Room/RoomEdit.vue'),
      props:true
    },
    {
      path: '/vats',
      name: 'VatIndex',
      component: () => import('../views/Vat/VatIndex.vue')
    },
    {
      path: '/vats/create',
      name: 'VatCreate',
      component: () => import('../views/Vat/VatCreate.vue')
    },
    {
      path: '/reservations',
      name: 'RerservationIndex',
      component: () => import('../views/Reservation/RerservationIndex.vue')
    },
    {
      path: '/reservations/create',
      name: 'RerservationCreate',
      component: () => import('../views/Reservation/RerservationCreate.vue')
    },
    {
      path: '/reservations/:id/edit',
      name: 'RerservationEdit',
      component: () => import('../views/Reservation/RerservationEdit.vue'),
      props:true
    },
    {
      path: '/users',
      name: 'UserIndex',
      component: () => import('../views/User/UserIndex.vue')
    },
    {
      path: '/users/create',
      name: 'UserCreate',
      component: () => import('../views/User/UserCreate.vue')
    },
    {
      path: '/users/:id/edit',
      name: 'UserEdit',
      component: () => import('../views/User/UserEdit.vue'),
      props:true
    },
  ]
})

export default router
