import React, { Component } from 'react';
import { Redirect, Route } from 'react-router-dom';

class SecureRoute extends Component {
    isSignedIn() {
        return this.props.isLoggedIn;
    }

    render() {
        const {component: Component, ...rest} = this.props;

        const renderRoute = props => {
            if (this.isSignedIn()) {
                return (
                    <Component {...props} />
                );
            }

            return (
                <Redirect to={{ pathname: '/', state: { from: this.props.location } }} />
            );
        }

        return (
            <Route {...rest} render={renderRoute}/>
        );
    }
}

export default SecureRoute;