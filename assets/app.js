import './styles/ColorPicker.css';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import './NiceAdmin/scss/style.scss';
import 'intl-tel-input/build/css/intlTelInput.css';
import './styles/app.css';

const loadEssentialJsFiles = () => {
    return Promise.all([
        import('./NiceAdmin/js/main.js'),
        import('./bootstrap.js')
    ]);
};

const lazyLoadChartJsFiles = () => {
    return Promise.all([
        import('echarts'),
        import('apexcharts'),

    ]).then(() => {
        return Promise.all([
            import('zrender/lib/zrender.js'),
            import('zrender/lib/core/util.js'),
            import('zrender/lib/core/env.js'),
            import('zrender/lib/core/timsort.js'),
            import('zrender/lib/core/Eventful.js'),
            import('zrender/lib/graphic/Text.js'),
            import('zrender/lib/tool/color.js'),
            import('zrender/lib/graphic/Path.js'),
            import('zrender/lib/tool/path.js'),
            import('zrender/lib/core/matrix.js'),
            import('zrender/lib/core/vector.js'),
            import('zrender/lib/core/Transformable.js'),
            import('zrender/lib/graphic/Image.js'),
            import('zrender/lib/graphic/Group.js')
        ]);
    });
};
