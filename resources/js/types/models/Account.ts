import { Media } from '@/types/models/Media'
import { Profile } from '@/types/models/Profile'

export interface Account {
    id: number;
    thumbnail: Media | null;
    profile: Profile | null;
}