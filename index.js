import { is_lucky } from 'luck';

export function flip(times) {
  const results = [];
  for (let i = 0; i < times; i++) {
    results.push(is_lucky());
  }
  return results;
}
