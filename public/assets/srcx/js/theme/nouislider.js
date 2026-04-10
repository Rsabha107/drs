/* -------------------------------------------------------------------------- */
/*                               noUiSlider                                   */
/* -------------------------------------------------------------------------- */
const nouisliderInit = () => {
  const { getData } = window.phoenix.utils;
  if (window.noUiSlider) {
    const elements = document.querySelectorAll('[data-nouislider]');
    elements.forEach(item => {
      const userOptions = getData(item, 'nouislider');
      const defaultOptions = {
        start: [10],
        connect: [true, false],
        step: 1,
        range: { min: [0], max: [100] },
        tooltips: true
      };
      const options = window._.merge(defaultOptions, userOptions);
      window.noUiSlider.create(item, { ...options });
    });
  }
};

export default nouisliderInit;
