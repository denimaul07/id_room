// ServiceIcons.js
import { h } from 'vue'

export const HomeIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('path', {
      d: 'M3 10.5L12 4l9 6.5V20a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4h-4v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5z',
      fill: '#d6b96b'
    })
  ])
])

export const BuildingIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('rect', { x: 4, y: 7, width: 16, height: 13, rx: 2, fill: '#d6b96b' }),
    h('rect', { x: 8, y: 3, width: 8, height: 4, rx: 1, fill: '#d6b96b' }),
    h('rect', { x: 9, y: 11, width: 2, height: 2, fill: '#fff' }),
    h('rect', { x: 13, y: 11, width: 2, height: 2, fill: '#fff' })
  ])
])

export const ToolsIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('path', {
      d: 'M21 2l-2 2 2 2-2 2-2-2-2 2 2 2-2 2-2-2-2 2 2 2-2 2-2-2-2 2 2 2-2 2',
      stroke: '#d6b96b', 'stroke-width': 2, 'stroke-linecap': 'round', 'stroke-linejoin': 'round'
    })
  ])
])

export const PaintRollerIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('rect', { x: 3, y: 5, width: 14, height: 6, rx: 2, fill: '#d6b96b' }),
    h('rect', { x: 7, y: 15, width: 4, height: 6, rx: 1, fill: '#d6b96b' }),
    h('rect', { x: 17, y: 7, width: 4, height: 2, rx: 1, fill: '#d6b96b' })
  ])
])

export const SofaIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('rect', { x: 3, y: 10, width: 18, height: 7, rx: 2, fill: '#d6b96b' }),
    h('rect', { x: 5, y: 17, width: 14, height: 3, rx: 1, fill: '#d6b96b' })
  ])
])

export const WrenchIcon = () => h('span', { style: { display: 'inline-block' } }, [
  h('svg', { width: 48, height: 48, fill: 'none', viewBox: '0 0 24 24' }, [
    h('path', {
      d: 'M22 14.92a5.5 5.5 0 0 1-7.92-7.92l-7.5 7.5a2.12 2.12 0 1 0 3 3l7.5-7.5z',
      fill: '#d6b96b'
    })
  ])
])
