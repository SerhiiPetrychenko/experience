<template>
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="/master"> {{ user.name }} </a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <ul class="nav d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <li class="nav-item">
        <button class="btn btn-sm btn-outline-light text-white order-1 order-sm-0" @click="logautClick()">
          Logout
        </button>
      </li>
    </ul>

  </nav>
</template>
<script>
  export default {
    name: 'AppNav',

    props: {
      user: {}
    },

    data() {
      return {

      }
    },

    methods: {
      logautClick() {
        axios.post('/logout')
          .then(response => {
            this.$router.push("/login")
          })
          .catch(error => {
            location.reload()
          })
      }
    }
  }
</script>
