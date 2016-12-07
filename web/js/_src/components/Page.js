import React, {PropTypes, Component} from 'react'

export default class Page extends Component {
    static propTypes = {
        year: PropTypes.number.isRequired,
        photos: PropTypes.array.isRequired,
        fetching: PropTypes.bool.isRequired,
        getPhotos: PropTypes.func.isRequired
    };

    onYearBtnClick(e) {
        this.props.getPhotos(+e.target.textContent)
    }

    render() {
        const {year, photos, fetching} = this.props;
        const years = [2016,2015,2014,2013,2012,2011,2010]

        return <div>
            <p>
                { years.map((item, index) =>
                    <object key={index}>
                        <button className='btn btn-default' onClick={::this.onYearBtnClick}>{item}</button>{' '}
                    </object>
                )}
            </p>
            <h3>Year {year}</h3>
            {
                fetching ?
                    <p>Loading...</p>
                    :
                    <p>You have {photos.length} photos.</p>
            }
        </div>
    }
}
