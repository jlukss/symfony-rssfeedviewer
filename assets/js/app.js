import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import { Route, BrowserRouter } from 'react-router-dom';
import Login from './components/Login';
import Register from './components/Register';
import Viewer from './components/Viewer';
import SecureRoute from './components/SecureRoute';

require('../css/app.css');

class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            'isLoggedIn': document.cookie.indexOf('is_logged_in') > -1
        }

        this.setIsLoggedIn = this.setIsLoggedIn.bind(this);
    }

    setIsLoggedIn() {
        document.cookie = 'is_logged_in=true';
        this.setState({
            'isLoggedIn': true
        })
    }

    render() {
        return (
            <BrowserRouter>
                <div>
                    <Route exact path="/" render={(props) => <Login setIsLoggedIn={this.setIsLoggedIn} {...props} />} />
                    <Route path='/register' component={Register}/>
                    <SecureRoute path="/viewer" component={Viewer} isLoggedIn={this.state.isLoggedIn} />
                </div>
            </BrowserRouter>
        )
    }
}

ReactDOM.render(<App />, document.getElementById('root'))