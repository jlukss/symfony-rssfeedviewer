import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEquals, faTimes, faCheck, faNotEqual } from '@fortawesome/free-solid-svg-icons'
import { Redirect } from 'react-router-dom';

import '../../css/Register.css';

class Register extends Component {
    constructor(props) {
        super(props);

        this.state = {
            'email': '',
            'passwordfirst': '',
            'passwordsecond': '',
            'emailvalid': false,
            'submitted': false,
            'errorfirst': '',
            'errorsecond': '',
            'erroremail': '',
            'error': ''
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    validateForm() {
        return this.state.email.length > 0 && this.state.passwordfirst.length > 0 && this.state.passwordfirst == this.state.passwordsecond;
    }

    async validateEmail() {
        if(this.state.email.indexOf('@') == -1) return;

        this.validationRequest = await fetch('/user/validate?email=' + this.state.email);

        const result = await this.validationRequest.json();
        
        if (this.state.emailvalid != result) {
            this.setState({
                'emailvalid': result
            });
        }
    }

    handleChange(event) {
      this.setState({
        [event.target.id]: event.target.value
      });
    }

    handleSubmit(event) {
      event.preventDefault();

      this.sendData();
    }

    componentDidUpdate() {
        this.validateEmail();
    }

    async sendData() {
        this.setState({
            'error': '',
            'errorfirst': '',
            'errorsecond': ''
        });

        const response = await fetch('/user/signup', {
            method: 'POST',
            body: JSON.stringify(
                {
                    email: this.state.email,
                    password: {
                        first: this.state.passwordfirst,
                        second: this.state.passwordsecond
                    }
                }),
            headers:{
              'Content-Type': 'application/json',
            }
          });
        
        if(response.status == 201) {
            this.setState({
                'submitted': true
            });
            return;
        }

        const json = await response.json();
        if(json.type == 'error') {
            var state = {
                error: json.message
            }

            for(var field in json.errors) {
                state['error' + field] = json.errors[field]
            };

            console.log(state);

            this.setState(state);
        }
    }

    render() {
        const renderError = (error) => {
            if (error != '') {
                return (<div className="error">{error}</div>)
            }
        }

        const renderEmailIcon = () => {
            if(this.state.emailvalid) {
                return (<FontAwesomeIcon icon={faCheck} className="ok"></FontAwesomeIcon>)
            }
            return (<FontAwesomeIcon icon={faTimes} className="error"></FontAwesomeIcon>)
        }

        const renderCompareIcon = () => {
            if(this.state.passwordfirst==this.state.passwordsecond) {
                return (<FontAwesomeIcon icon={faEquals} className="ok"></FontAwesomeIcon>)
            }
            return (<FontAwesomeIcon icon={faNotEqual} className="error"></FontAwesomeIcon>)
        }

        if(!this.state.submitted) {
            return (
            <div id="register" className="form">
                <form onSubmit={this.handleSubmit}>
                    {renderError(this.state.error)}
                    <div className="field">
                        <label>Email</label>
                        <input 
                            id="email"
                            autoFocus
                            type="email"
                            onChange={this.handleChange}
                            value={this.state.email}
                        />
                        {renderEmailIcon()}
                        {renderError(this.state.erroremail)}
                    </div>
                    <div className="field">
                        <label>Password</label>
                        <input 
                            id="passwordfirst"
                            onChange={this.handleChange}
                            value={this.state.password}
                            type="password"
                        />
                        {renderError(this.state.errorfirst)}
                    </div>
                    <div className="field">
                        <label>Password repeated</label>
                        <input 
                            id="passwordsecond"
                            onChange={this.handleChange}
                            value={this.state.password}
                            type="password"
                        />
                        {renderCompareIcon()}
                        {renderError(this.state.errorsecond)}
                    </div>
                    <button
                        disabled={!this.validateForm()}
                        type="submit"
                    >
                        Register
                    </button>
                    <Link to="/">Login</Link>
                </form>
            </div>
            )
            } else {
                return <Redirect to={{
                    pathname: "/",
                    state: {
                        success: "User created: " + this.state.email
                    }
                }} />
            }
    }
}

export default Register;