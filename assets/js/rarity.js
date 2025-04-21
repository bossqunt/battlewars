export const RARITY = {
  1: {
    label: 'Common',
    badgeClass: 'bg-black text-white',
    borderClass: '!border-[2px] !border-grey-600 text-black'
  },
  2: {
    label: 'Uncommon',
    badgeClass: 'bg-green-600 text-white',
    borderClass: '!border-[2px] !border-green-600 text-green-600'
  },
  3: {
    label: 'Rare',
    badgeClass: 'bg-blue-600 text-white',
    borderClass: '!border-[2px] !border-blue-600 text-blue-600'
  },
  4: {
    label: 'Epic',
    badgeClass: 'bg-purple-600 text-white',
    borderClass: '!border-[2px] !border-purple-600 text-purple-600'
  },
  5: {
    label: 'Legendary',
    badgeClass: 'bg-orange-500 text-white',
    borderClass: '!border-[2px] !border-orange-500 text-white-500'
  },
  6: {
    label: 'Godly',
    badgeClass: 'bg-red-400 text-white',
    borderClass: '!border-[2px] !border-red-500 text-red-500'
  },
  default: {
    label: 'Unknown',
    badgeClass: 'bg-gray-300 text-black',
    borderClass: '!border-[2px] !border-gray-300 text-gray-700'
  }
};

export function getRarityLabel(rarity) {
  return (RARITY[rarity] || RARITY.default).label;
}
export function getRarityBadgeClass(rarity) {
  return (RARITY[rarity] || RARITY.default).badgeClass;
}
export function getRarityBorderClass(rarity) {
  return (RARITY[rarity] || RARITY.default).borderClass;
}
