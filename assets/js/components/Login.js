import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Redirect } from 'react-router-dom';

import '../../css/Login.css';

class Login extends Component {
    constructor(props) {
        super(props);

        console.log(props);

        this.state = {
            'email': '',
            'password': '',
            'error': '',
            'login': false
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    validateForm() {
        return this.state.email.length > 0 && this.state.password.length > 0;
    }

    handleChange(event) {
        this.setState({
            [event.target.id]: event.target.value
        });
    }

    async handleSubmit(event) {
        event.preventDefault();

        let res = await fetch('/user/login',
            {
                method: 'POST',
                body: JSON.stringify(this.state),
                credentials: 'same-origin',
                headers:{
                    'Content-Type': 'application/json',
                }
            }
        );

        if(res.status == 200) {
            this.props.setIsLoggedIn();

            this.setState({
                'error': '',
                'login': true
            });

            return;
        }

        let json = await res.json();

        this.setState({
            'error': json.message
        });
    }

    render() {
        const renderError = () => {
            if (this.state.error != '') {
                return (<div className="error">{this.state.error}</div>)
            }
        }

        if(!this.state.login) {
            return (
                <div id="login" className="form">
                    <form onSubmit={this.handleSubmit}>
                        {renderError()}
                        <div className="field">
                            <label>Email</label>
                            <input 
                                id="email"
                                autoFocus
                                type="email"
                                onChange={this.handleChange}
                                value={this.state.email}
                            />
                        </div>
                        <div className="field">
                            <label>Password</label>
                            <input 
                                id="password"
                                onChange={this.handleChange}
                                value={this.state.password}
                                type="password"
                            />
                        </div>
                        <button
                            disabled={!this.validateForm()}
                            type="submit"
                        >
                            Login
                        </button>
                        <Link to="/register">Register</Link>
                    </form>
                </div>
            )
        } else {
            return <Redirect to="/viewer" />
        }
    }
}

export default Login;