import { DashboardContext } from '../providers/dashboard-provider';
import { useContext } from 'react';

export const useDashboardContext = () => {
	return useContext( DashboardContext );
};
