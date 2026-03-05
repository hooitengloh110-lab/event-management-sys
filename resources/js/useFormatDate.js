export const useDateFormat = () => {
  const formatDate = (date) => {
    if (!date) return ''

    const [datePart, timePart] = date.split(' ')
    const [year, month, day] = datePart.split('-')
    const [hour, minute] = timePart.split(':')

    const monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ]

    const h = parseInt(hour)
    const period = h >= 12 ? 'PM' : 'AM'
    const hour12 = h % 12 || 12

    return `${monthNames[parseInt(month) - 1]} ${day}, ${year}, ${hour12.toString().padStart(2, '0')}:${minute} ${period}`
  }
  
  const formatDateShort = (date) => {
    if (!date) return ''
    
    const [datePart] = date.split(' ')
    const [year, month, day] = datePart.split('-')
    
    const monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ]
    
    return `${monthNames[parseInt(month) - 1]} ${day}, ${year}`
  }
  
  const formatTime = (date) => {
    if (!date) return ''
    
    const [, timePart] = date.split(' ')
    const [hour, minute] = timePart.split(':')
    
    const h = parseInt(hour)
    const period = h >= 12 ? 'PM' : 'AM'
    const hour12 = h % 12 || 12
    
    return `${hour12}:${minute} ${period}`
  }

  const formatDateTime = (dateStr) => {
    if (!dateStr) return ''

    const d = new Date(dateStr)

    if (isNaN(d.getTime())) return ''

    const Y = d.getFullYear()
    const M = String(d.getMonth() + 1).padStart(2, '0')
    const D = String(d.getDate()).padStart(2, '0')

    let H = d.getHours()
    const m = String(d.getMinutes()).padStart(2, '0')

    const ampm = H >= 12 ? 'PM' : 'AM'
    H = H % 12
    if (H === 0) H = 12
    const hourStr = String(H).padStart(2, '0')

    return `${Y}/${M}/${D} ${hourStr}:${m} ${ampm}`
  }
  
  return {
    formatDate,
    formatDateShort,
    formatTime,
    formatDateTime
  }
}