import { generateClasses } from "@formkit/themes"
import { pl, en } from "@formkit/i18n"

export default {
  locales: { pl, en },
  config: {
    classes: generateClasses({
      global: {
        outer: "formkit-disabled:opacity-50",
        label: "font-semibold",
        messages: "list-none p-0 mt-1 mb-0",
        message: "text-red-500 mb-1 text-sm font-medium",
        input:
          "w-full border-1 border-gray-400 rounded-xl shadow-sm font-normal focus:ring-primary formkit-invalid:border-red-500",
      },
      submit: {
        input:
          "p-3 text-white rounded-xl font-medium bg-primary hover:bg-secondary text-base",
      },
      button: {
        input:
          "p-3 text-white rounded-xl font-medium bg-primary hover:bg-secondary text-base flex flex-row justify-center",
      },
    }),
  },
}