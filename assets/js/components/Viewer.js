import React, { Component } from 'react';

import '../../css/Viewer.css';

class Viewer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            'words': [],
            'headlines': []
        }
    }

    async componentDidMount() {
        let res = await fetch('/feed/get', {
            credentials: 'same-origin',
            headers:{
                'Content-Type': 'application/json',
            }
        });

        if (res.status == 200) {
            let data = await res.json();

            this.setState(data);

            return;
        }

        this.props.history.push('/');
    }

    render() {
        return (
            <div>
                <div id="words">
                    <h1>Top words</h1>
                    {
                        Object.keys(this.state.words).map((word, i) => {
                                return <span className="word" key={i}>{word}({this.state.words[word]})</span>
                            }
                        )
                    }
                </div>
                <div id="headlines">
                    <h1>Headlines</h1>
                    {
                        this.state.headlines.map((article, i) => {
                                return <div className="headline" key={i}>
                                    <h2><a href="{article.link}">{article.title}</a></h2>
                                    <div className="summary" dangerouslySetInnerHTML={{__html: article.summary}}></div>
                                </div>
                            }
                        )
                    }
                </div>
            </div>
        )
    }
}

export default Viewer;