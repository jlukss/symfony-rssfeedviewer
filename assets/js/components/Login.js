import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Redirect } from 'react-router-dom';

import '../../css/Login.css';

class Login extends Component {
    constructor(props) {
        super(props);

        let success = '';
        if (this.props.location.state !== undefined) {
            success = this.props.location.state.success
        }

        this.state = {
            'email': '',
            'password': '',
            'error': '',
            'success': success
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
                'error': ''
            });


            this.props.history.push('/viewer');
            return;
        }

        let json = await res.json();

        this.setState({
            'error': json.message,
            'success': ''
        });
    }

    render() {
        const renderSuccess = () => {
            console.log(this.props.location);
            if (this.state.success != '') {
                return (<div className="success">{this.state.success}</div>)
            }
        }
        const renderError = () => {
            if (this.state.error != '') {
                return (<div className="error">{this.state.error}</div>)
            }
        }

        return (
            <div id="login" className="form">
                <form onSubmit={this.handleSubmit}>
                    {renderSuccess()}
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
    }
}

export default Login;