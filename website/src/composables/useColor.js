export const colorWithOpacity = (hex, opacity = 0.9) => {
    if (!hex) return hex

    hex = hex.replace('#', '')
    const r = parseInt(hex.slice(0, 2), 16)
    const g = parseInt(hex.slice(2, 4), 16)
    const b = parseInt(hex.slice(4, 6), 16)

    return `rgba(${r}, ${g}, ${b}, ${opacity})`
}
