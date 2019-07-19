<template>
    <div class="sign-up" style="margin: auto;">
        <form>
            <h2>Sign Up</h2>
            <hr>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" v-model="signUpDetails.name" class="form-control">
                <span class="help-block" v-if="errorsName">
                    {{nameError}}
                </span>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" v-model="signUpDetails.email" class="form-control">
                <span class="help-block" v-if="errorsEmail">
                    {{emailError}}
                </span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" v-model="signUpDetails.password"  class="form-control">
                <span class="help-block" v-if="errorsPassword">
                    {{passwordError}}
                </span>
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input type="password" name="password-confirm" id="password-confirm" v-model="signUpDetails.password_confirmation" class="form-control">
            </div>
            <br>
            <div class="form-group">
                <button type="submit" @click.prevent="signUpPost" class="btn btn-primary">Done</button>
                <router-link to="/" class="btn btn-link">Back to Login Form</router-link>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            signUpDetails:{
                name: '',
                email: '',
                password: '',
                roles: 'officer'
            },
            errorsName: false, 
            errorsEmail: false,
            errorsPassword: false,
            errorsConfirmPassword: false,
            nameError: null,
            emailError: null,
            passwordError: null
        }
    },
    methods: {
        signUpPost() {
            let _self = this;
            axios.post('/register', _self.signUpDetails)
            .then((response) => {
                location.reload();
            })
            .catch((error)=> {
                var errors = error.response;
                console.log(error.response);

                _self.errorsName = false;
                _self.errorsEmail = false;
                _self.errorsPassword = false;
                
                if(errors.statusText === 'Unprocessable Entity' || errors.status === 422) {
                    if(errors.data.errors) {
                        if(errors.data.errors.name) {
                            _self.errorsName = true;
                            _self.nameError = _.isArray(errors.data.errors.name) ? errors.data.errors.name[0]: errors.data.errors.name;
                        }
                        if(errors.data.errors.email) {
                            _self.errorsEmail = true;
                            _self.emailError = _.isArray(errors.data.errors.email) ? errors.data.errors.email[0]: errors.data.errors.email;
                        }
                        if(errors.data.errors.password) {
                            _self.errorsPassword = true;
                            _self.passwordError = _.isArray(errors.data.errors.password) ? errors.data.errors.password[0]: errors.data.errors.password;
                        }
                    }
                }
            });
        }
    }
}
</script>