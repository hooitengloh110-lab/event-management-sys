export const formatCurrency = (value, showSymbol = true) => {
  if (value === null || value === undefined || value === '') return showSymbol ? 'RM 0' : '0'

  const number = Number(value)
  if (isNaN(number)) return showSymbol ? 'RM 0' : '0'

  return number.toLocaleString('en-MY', {
    style: 'currency',
    currency: 'MYR',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).replace('MYR', showSymbol ? 'RM' : '')
}