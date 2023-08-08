import { usePage } from '@inertiajs/vue3'

export function __(key, replace = {}) {
  let translation = usePage().props?.language[key]
    ? usePage().props.language[key]
    : key

  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })

  return translation
}