import axios from 'axios';
import { loadLocaleAsync } from './i18n';

// TODO Add tests
/*
describe('i18n', () => {
  test('should set new language', async () => {
    axios.get = jest.fn().mockReturnValue(Promise.resolve({ data: { foo: 'foo' } }));

    await loadLocaleAsync('fr');

    expect(axios.get).toHaveBeenCalledTimes(1);

    await loadLocaleAsync('fr');

    expect(axios.get).toHaveBeenCalledTimes(1);

    await loadLocaleAsync('en');

    expect(axios.get).toHaveBeenCalledTimes(2);
  });

  test('should throw error', async () => {
    axios.get = jest.fn().mockReturnValue(Promise.reject({ message: 'foo' }));

    try {
      await loadLocaleAsync('fr');
    } catch (e) {
      expect(e).toEqual({ message: 'foo' });
    }
  });
});
*/