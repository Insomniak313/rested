import React, { Component } from 'react';

import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';
import ExpansionPanel from '@material-ui/core/ExpansionPanel';
import ExpansionPanelSummary from '@material-ui/core/ExpansionPanelSummary';
import ExpansionPanelDetails from '@material-ui/core/ExpansionPanelDetails';
import Typography from '@material-ui/core/Typography';
import ExpandMoreIcon from '@material-ui/icons/ExpandMore';

const styles = {
	root: {
		flexGrow: 1,
	},
	grow: {
		flexGrow: 1,
	},
	menuButton: {
		marginLeft: -12,
		marginRight: 20,
	},
};

/**
 * Composant permettant d'afficher le front
 */
class App extends Component {

    /**
     * Constructeur
     * @param props
     */
	constructor(props) {
		super(props);

		this.state = {
			regions: [],
		};
	}

    /**
     * Méthode appelée avant que le composant soit affiché
     * On récupère les régions depuis le back
     */
	componentDidMount() {
		fetch('http://localhost:10082/api/listRegions')
			.then(response => response.json())
			.then(data => this.setState({ regions: data }));

	}

    /**
     * Méthode d'affichage du composant
     * @returns {boolean}
     */
	render() {
		const { classes } = this.props;
		const { regions } = this.state;

		return (
			<div className={classes.root}>
				<AppBar position="static">
					<Toolbar>
						<IconButton className={classes.menuButton} color="inherit" aria-label="Menu">
							<MenuIcon />
						</IconButton>
						<Typography variant="h6" color="inherit" className={classes.grow}>
							Rested Front
						</Typography>
					</Toolbar>
				</AppBar>

				<br/>

				{
					regions.map((region) => {
						return (
							<ExpansionPanel>
								<ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
									<Typography className={classes.heading}>{ region.nom } [{ region.code }]</Typography>
								</ExpansionPanelSummary>
								<ExpansionPanelDetails>
									<Typography>
										<ul>
										{
											region.departements.map((departement) => {
												return (
													<li>{ departement.nom } [{departement.code}]</li>
												);
											})
										}
										</ul>
									</Typography>
								</ExpansionPanelDetails>
							</ExpansionPanel>
						);
					})
				}

			</div>
		);
	}
}

App.propTypes = {
	classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(App);
