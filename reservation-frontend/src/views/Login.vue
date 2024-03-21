<template>
  <div class="login p-12">
    <div class="bg-white max-w-4xl mx-auto p-4 grid justify-center">
      <h2 class="text-center font-bold text-2xl">Login</h2>
        <form class="p-5">
          <div >
            <InputLabel>Email address<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="email"  v-model="details.email"               placeholder="Enter email" />
            </div>
            <div v-if="errors.email">
              <div v-for="error in errors.email" :key="error" class="mt-2">
                <InputError       
                  :message="error" 
                />
              </div>
            </div>
          </div>
          <div class="my-5">
            <InputLabel>Password<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="password"  v-model="details.password"               placeholder="Enter Password" />
            </div>
            <div v-for="error in errors.password" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
        

          <mainBtn type="button"  @click="login" :btn_type="1">Login</mainBtn> 

        </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import mainBtn from '@/components/button.vue'

export default {
  name: "Home",
  components: {
    InputLabel,
InputError,
mainBtn,
TextInput
  },
  data: function() {
    return {
      details: {
        email: null,
        password: null
      }
    };
  },

  computed: {
    ...mapGetters(["errors"])
  },

  mounted() {
    this.$store.commit("setErrors", {});
  },

  methods: {
    ...mapActions("auth", ["sendLoginRequest"]),

    login: function() {
      this.sendLoginRequest(this.details).then(() => {
        this.$router.push({ name: "home" });
      });
    }
  }
};
</script>
